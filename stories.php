<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stories | RePurposeHub</title>
  <style>
    body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
}

header {
  text-align: center;
  padding: 20px;
  background-color: #4CAF50;
  color: white;
}

.stories-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 20px;
}

.story-card {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  width: 100%;
  margin: 20px;
  padding: 15px;
}

.user-info {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.profile-pic {
  border-radius: 50%;
  width: 40px;
  height: 40px;
  margin-right: 10px;
}

.username {
  font-weight: bold;
  font-size: 1.1em;
}

.story-image {
  width: 100%;
  height: auto;
  border-radius: 8px;
  margin-top: 10px;
}

.caption {
  font-style: italic;
  color: #555;
  margin: 10px 0;
}

.comments-section {
  margin-top: 10px;
}

.comment {
  display: flex;
  align-items: baseline;
  margin-bottom: 8px;
}

.comment-username {
  font-weight: bold;
  margin-right: 5px;
}

.comment-form {
  display: flex;
  margin-top: 10px;
}

.comment-input {
  flex: 1;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.comment-button {
  padding: 8px 15px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

  </style>
</head>
<body>

  <header>
    <h1>Community Stories</h1>
    <p>See how donations are making a difference!</p>
  </header>

  <section class="stories-container">
    <!-- Sample Story Card -->
    <div class="story-card">
      <div class="user-info">
        <img src="images/welcoming.jpg" alt="User Profile" class="profile-pic">
        <span class="username">John Doe</span>
      </div>
      <img src="donation-image.jpg" alt="Donation Item" class="story-image">
      <p class="caption">“So happy to see this item reach a new home!”</p>
      <div class="comments-section">
        <h3>Comments</h3>
        <div class="comment">
          <span class="comment-username">Anna Smith:</span>
          <p>“Such a great initiative! Happy to see this story!”</p>
        </div>
        <form class="comment-form">
          <input type="text" placeholder="Add a comment..." class="comment-input">
          <button type="submit" class="comment-button">Post</button>
        </form>
      </div>
    </div>
    <!-- End of Story Card -->
  </section>

</body>
</html>
