# API Endpoints

## Posts Endpoints

### GET /posts
returns a list of posts, with author info, number of likes, comments, views for each post.

### GET /posts/{post}
returns a single post, with author info, number of likes, comments, views for each post.

### POST /posts
stores a post for a user.

**Request Body:**
- `title` (string): the post title.
- `body` (string): the post body.

**Response:**
- 201 Created
- 404 Not Found
- 422 Unprocessable Content

### PATCH /posts/{post}
updates a post for a user.

**Request Body:**
- `title` (string): the post title.
- `body` (string): the post body.

**Response:**
- 200 Ok
- 403 Forbidden
- 404 Not Found
- 422 Unprocessable Content

### DELETE /posts/{post}
deletes a post for a user.

**Response:**
- 204 No Content
- 403 Forbidden
- 404 Not Found
