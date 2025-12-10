# 📚 API Documentation

## Overview
Complete REST API for all 226 marketing tools.

## Authentication
```bash
Authorization: Bearer YOUR_API_TOKEN
```

## Base URL
```
https://marketingtool.pro/api
```

## Endpoints

### Google Ads API
```bash
POST /api/google-ads/campaigns
Content-Type: application/json

{
  "campaignName": "Summer Sale",
  "budget": 5000
}
```

### Meta Ads API
```bash
POST /api/meta-ads/campaigns
Content-Type: application/json

{
  "campaignName": "Product Launch",
  "budget": 3000
}
```

### AI Content API
```bash
POST /api/openai/generate
Content-Type: application/json

{
  "toolType": "blog-post",
  "topic": "AI Marketing"
}
```

## Rate Limits
- **Free**: 100 requests/hour
- **Pro**: 1,000 requests/hour
- **Enterprise**: Unlimited

## Error Codes
- `200` - Success
- `400` - Bad Request
- `401` - Unauthorized
- `429` - Rate Limit
- `500` - Server Error

For complete docs visit: https://marketingtool.pro/help-center
