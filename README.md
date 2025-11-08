# API Endpoints

## Posts Endpoints

### GET /posts

Retrieve a list of posts. Each item includes author details and aggregated counts for likes and comments.

### GET /posts/{post}

Retrieve a single post by its identifier. The response includes the full post content, author information, and like/comment counts.

### POST /posts

Create a new post for the authenticated user.

**Request Body:**

-   `title` (string): the post title.
-   `body` (string): the post body.

**Response:**

-   201 Created
-   401 Unauthorized
-   422 Unprocessable Content

### PATCH /posts/{post}

Update an existing post owned by the authenticated user. Supply any fields that should be changed (title and/or body).

**Request Body:**

-   `title` (string): the post title.
-   `body` (string): the post body.

**Response:**

-   200 Ok
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found
-   422 Unprocessable Content

### DELETE /posts/{post}

Delete a post owned by the authenticated user.

**Response:**

-   204 No Content
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found

## Comments Endpoints

### GET /posts/{post}/comments

Retrieve all comments for the specified post. Each comment includes author details and the number of likes.

### POST /posts/{post}/comments/{comment}

Create or submit a comment related to a post (endpoint includes the comment identifier as specified).

**Request Body:**

-   `body` (string): the comment body.

**Response:**

-   201 Created
-   401 Unauthorized
-   404 Not Found
-   422 Unprocessable Content

### PATCH /posts/{post}/comment

Modify an existing comment for the given post.

**Request Body:**

-   `body` (string): the comment body.

**Response:**

-   200 Ok
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found
-   422 Unprocessable Content

### DELETE /posts/{post}/comments/{comment}

Remove a comment from the specified post.

**Response:**

-   204 No Content
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found
