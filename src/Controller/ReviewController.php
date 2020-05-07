<?php declare(strict_types=1);

namespace Lorrx\ReviewApiController\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Context;
use Shopware\Core\System\SalesChannel\SalesChannelContext;


/**
 * Class ReviewController
 * @package Lorrx\ReviewApiController\Controller
 *
 * @RouteScope(scopes={"sales-channel-api"})
 */
class ReviewController extends AbstractController
{
    /**
     * @Route("/sales-channel-api/v1/product/{productId}/review", name="sales-channel-api.action.lorrx.get.product-review", methods={"GET"})
     * @param string $productId
     * @return JsonResponse
     */
    public function getProductReview(string $productId): JsonResponse
    {
        $reviewRepository = $this->container->get('product_review.repository');
        return new JsonResponse($reviewRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('productId', $productId))
                ->addFilter(new EqualsFilter('status', true)),
            Context::createDefaultContext()
        ));
    }

    /**
     * @Route("/sales-channel-api/v1/product/{productId}/review", name="sales-channel-api.action.lorrx.post.product-review", methods={"POST"})
     * @param string $productId
     * @param Request $request
     * @param SalesChannelContext $context
     * @return JsonResponse
     */
    public function createProductReview(string $productId, Request $request, SalesChannelContext $context): JsonResponse
    {
        if ($context->getCustomer() == null) {
            return new JsonResponse(['message' => 'User seems to be unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $reviewRepository = $this->container->get('product_review.repository');

        $customer_reviews = $reviewRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('productId', $productId))
                ->addFilter(new EqualsFilter('customerId', $context->getCustomer()->get('id')))
                ->addFilter(new EqualsFilter('productId', $productId)),
            Context::createDefaultContext()
        );

        if (count($customer_reviews) == 0) {
            $reviewRepository->create(
                [
                    [
                        'productId' => $productId,
                        'customerId' => $context->getCustomer()->get('id'),
                        'salesChannelId' => $context->getSalesChannel()->get('id'),
                        'languageId' => $request->request->get('language'),
                        'title' => $request->request->get('title'),
                        'content' => $request->request->get('content'),
                        'points' => $request->request->get('points'),
                        'status' => true
                    ]
                ],
                Context::createDefaultContext()
            );
            return new JsonResponse(null, JsonResponse::HTTP_CREATED);
        }

        return new JsonResponse(['message' => 'Only one comment for each product and customer'],
            JsonResponse::HTTP_FORBIDDEN);
    }

    /**
     * @Route("/sales-channel-api/v1/product/{productId}/review/{reviewId}", name="sales-channel-api.action.lorrx.patch.product-review", methods={"PATCH"})
     * @param string $reviewId
     * @param Request $request
     * @param SalesChannelContext $context
     * @return JsonResponse
     */
    public function updateProductReview(string $reviewId, Request $request, SalesChannelContext $context): JsonResponse
    {
        if ($context->getCustomer() == null) {
            return new JsonResponse(['message' => 'User seems to be unauthorized'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $reviewRepository = $this->container->get('product_review.repository');

        $customer_reviews = $reviewRepository->search(
            (new Criteria())
                ->addFilter(new EqualsFilter('id', $reviewId)),
            Context::createDefaultContext()
        );

        if (count($customer_reviews->getElements()) == 0) {
            return new JsonResponse(['message' => 'Review not found.'], JsonResponse::HTTP_NOT_FOUND);
        }

        $reviewRepository->update(
            [
                [
                    'id' => $reviewId,
                    'languageId' => $request->request->get('language'),
                    'title' => $request->request->get('title'),
                    'content' => $request->request->get('content'),
                    'points' => $request->request->get('points'),
                ]
            ],
            Context::createDefaultContext()
        );

        return new JsonResponse(null, JsonResponse::HTTP_OK);
    }
}
