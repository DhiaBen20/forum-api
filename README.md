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

### GET /comments
Retrieves all comments for the specified post or comment. 

**Request params**
-   `parent` (int): id of comment parent
-   `type` (string): comment or post

**Response:**
-   200 Ok
-   401 Unauthorized
-   404 Not Found

### POST /comments
Create a new comment for the specified parent type.

**Request Body**
-   `type` (string): comment_to_post or reply_to_comment or reply_to_post
-   `body` (string): the comment body
-   `post` (int): required if type is comment_to_post
-   `comment` (int): required if type is reply_to_comment or reply_to_reply
-   `replyTo` (int): required if type is reply_to_reply

**Response:**
-   201 Created
-   401 Unauthorized
-   422 Unprocessable Content

### PATCH /comments/{comment}

Update an existing comment owned by the authenticated user.

**Request Body:**

-   `body` (string): the comment body.

**Response:**

-   200 Ok
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found
-   422 Unprocessable Content

### DELETE /comments/{comment}

Removes the specified comment

**Response:**

-   204 No Content
-   401 Unauthorized
-   403 Forbidden
-   404 Not Found

## Likes Endpoints
### POST /likes/posts/{likeable}
### POST /likes/comments/{likeable}
Stores a like for the current user to the post or the comment with the id likeable

**Response:**
- 204 No Content
- 401 Unauthorized
- 404 Not found
- 409 Conflict

### DELETE /likes/posts/{likeable}
### DELETE /likes/comments/{likeable}
Removes a like for the current user from  the post or the comment with the id likeable

**Response:**
- 204 No Content
- 401 Unauthorized
- 404 Not found
