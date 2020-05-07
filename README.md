# Product Review API
_by Lorrx_

## Usage

### List per product
```json
GET /sales-channel-api/v1/product/{productId}/review
--header sw-access-key='SWSCOWV0S0ZLMTV4BNVQCZLTVG' 

Response:
{
  "total": 1,
  "aggregations": [],
  "elements": {
    "33163ac4a3ba4a37904090b3807a9d5f": {
      "productId": "d5df5978864b4dd5b684a63303e8ad19",
      "customerId": "7dcae466783a44f799e3c03112b668a5",
      "salesChannelId": "98432def39fc4624b33213a56b8c944d",
      "languageId": "2fbb5fe2e29a4d70aa5854ce7ce3e20b",
      "externalUser": null,
      "externalEmail": null,
      "points": 1,
      "status": true,
      "comment": null,
      "salesChannel": null,
      "language": null,
      "customer": null,
      "product": null,
      "content": "A new comment!!!",
      "title": "Magni quo ea ipsa sunt.",
      "_uniqueIdentifier": "33163ac4a3ba4a37904090b3807a9d5f",
      "versionId": null,
      "translated": [],
      "createdAt": "2020-05-07T14:24:13+00:00",
      "updatedAt": null,
      "extensions": {
        "foreignKeys": {
          "extensions": []
        }
      },
      "id": "33163ac4a3ba4a37904090b3807a9d5f",
      "productVersionId": "0fa91ce3e96a4bc2be4bd9ce752c3425"
    }
  },
  "extensions": []
}
```

### Create
```json
POST /sales-channel-api/v1/product/{productId}/review
--header sw-access-key='SWSCOWV0S0ZLMTV4BNVQCZLTVG' 
--header sw-context-token='zTGmpd19maCTV4k7wlNJizTXFWeHWyuE'

{
	"points": 1,
	"content": "A new comment!!!",
	"title": "Magni quo ea ipsa sunt.",
	"language": "2fbb5fe2e29a4d70aa5854ce7ce3e20b"
}
```

### Update
```json
PATCH /sales-channel-api/v1/product/{productId}/review/{reviewId}
--header sw-access-key='SWSCOWV0S0ZLMTV4BNVQCZLTVG' 
--header sw-context-token='zTGmpd19maCTV4k7wlNJizTXFWeHWyuE'

{
	"points": 3,
	"content": "A new comment v3!!!",
	"title": "Magni quo ea ipsa sunt. v3",
	"language": "2fbb5fe2e29a4d70aa5854ce7ce3e20b"
}
```
