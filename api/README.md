# Cecilian Student Portal API

This is the official API reference for the Cecilian Student Portal, which provides access to various features of the student portal such as the dashboard, newsfeed, calendar, and services. This API is versioned under `/v1` and is designed to be used by client applications such as web and mobile apps.
## Authentication

All requests to the API must be authenticated using a valid access token. To obtain an access token, send a POST request to the `/api/auth/token` endpoint with your credentials in the request body.

#### Get all items

```json
POST /api/auth/token HTTP/1.1
Host: example.com
Content-Type: application/json

{
  "username": "your_username",
  "password": "your_password"
}
```
The response will contain an access token that you can use to authenticate subsequent requests to the API.
 
```json
{
  "access_token": "your_access_token",
  "token_type": "bearer"
}
```

### Endpoint

#### GET `/api/dashboard`

Retrieves the activity and schedule for the current day for the authenticated user.

```json
{
  "activity": "your_activity",
  "schedule": "your_schedule"
}
```

#### GET `/api/grades`

Retrieves the grades and progress for the authenticated user.

```json
{
  "grades": "your_grades",
  "progress": "your_progress"
}
```

#### GET `/api/news`

Retrieves news and updates from different organizations and departments.

```json
{
  "news": "your_news"
}
```

#### GET `/api/calendar`

Retrieves the calendar of activities.

```json
{
  "calendar": "your_calendar"
}
```

#### GET `/api/services`

Retrieves the services offered by the school.

```json
{
  "services": "your_services"
}
```