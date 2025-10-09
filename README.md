# API Endpoints

## Threads Endpoints

### GET /threads
returns a list of threads, with author info, number of likes, comments, views for each thread.

### GET /threads/{thread}
returns a single thread, with author info, number of likes, comments, views for each thread.

### POST /threads
stores a thread for a user.

**Request Body:**
- `title` (string): the thread title.
- `body` (string): the thread body.

**Response:**
- 201 Created
- 404 Not Found
- 422 Unprocessable Content

### PATCH /threads/{thread}
updates a thread for a user.

**Request Body:**
- `title` (string): the thread title.
- `body` (string): the thread body.

**Response:**
- 200 Ok
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Content

### DELETE /threads/{thread}
deletes a thread for a user.

**Response:**
- 204 No Content
- 403 Forbidden
- 404 Not Found
