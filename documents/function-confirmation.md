# Function Confirmation
This is a file for confirming the functions.
#
## Manager: 
Customer info: type, location, the number of the accounts the customer holds, the total number of post the customer published, the total quantity of likes/reposts/comments the customer gets.

- Blog-Site Manager Logo: 
    HTML \<a> attribute, onclick -> refresh the page.
### Tools

- Basic Tools:
    1. Reset: Drop all tables
    2. Basic Data: Show the number of the customers and the blogs which are related to this customer.
    3. Show all Customers: List all the customers with the customers' info (type, location, the number of the accounts the customer holds, the total number of post the customer published, the total quantity of likes/reposts/comments the customer gets)
- Show by Filter:
    - Type: select the type and return info of all the customers who are in this type, but without type since we've already select the type.
    - Location: select the location and return info of all the customers who are in this location, but without location since we've already select the location.
- General Search: 
    - Search by the input username and get the manager with this name.
    - Results box should show all the customer with the username, also the number of related posts
    of shown account, all the like/repost/comments the account gets.
        - multiple customer may have the same name.
- Advanced Search:
    - Search by Customer ID: Enter the customer id, and get the customer info (type, location, the number of the accounts the customer holds, the total number of post the customer published, the total quantity of likes/reposts/comments the customer gets).
    - Search by Post Title: Enter the post title, and get the customer info of the one who have published a post with this post title, if no such post or no such person, return "no result".
    - Search by Account ID: Enter the account id, and get the customer info of the one who owned it.
- Edit Customer Info:
    - Enter customer id: Just Enter Account id.
    - Edit customer name: Submit new name of customer.
    - Edit customer location: Submit new location.
    - Edit customer type: Submit new type.
    - Delete Customer: Delete the customer and all the info that belong to him/her/they (Accounts & Posts)

#
## Customer

### Logo
- Blog-Site Manager Logo: 
    HTML \<a> attribute, onclick -> refresh the page.
### Tools

- Basic Tools:
    1. Reset: Drop all tables
    2. Basic Data: Show the number of the accounts and the blogs which are related to this customer.
    3. Show all Accounts: List all the accounts with the accounts' info (quantity of likes/reposts/comments).
- General Search: 
    - Search by the input username and get the account with this username.
    - Results box should show all the accounts with the username, also the number of related posts
    of shown account, all the like/repost/comments the account gets.
- Advanced Search:
    - Search by Post ID: Enter the post id, and get the account who post with this post id, if no such post,
    return "no result"
    - Search by Post Title: Enter the post title, and get the account who post with this post title, if no such post,
    return "no result"
    - Search by Tag ID: Enter the tag id, and get the account who post with this tag id, if no such post,
    return "no result"     
    - Search by Tag Name: Enter the tag name, and get all the accounts who post with this tag name, if no such post,
    return "no result"
        - There may be many posts with the same tag name.
- Edit Account Info:
    - Enter account id: Just Enter Account id.
    - Delete account: Delete the account and all the info that belong to it.

*** suggestion: The edit account name function should be deleted. ***

#
## Accounts

### Logo
- Blog-Site Manager Logo: 
    HTML \<a> attribute, onclick -> refresh the page.
### Tools

- Basic Tools:
    1. Reset: Drop all tables
    2. Basic Data: Show the number of the accounts and the blogs which are related to this customer.
    3. Show all Posts: List all the posts with the post' info (quantity of likes/reposts/comments).
- Show by Filter:
    - Type: select the type and return info of all the posts which are in this type, but without type since we've already select the type.
    - Quantity of Likes: Show all posts within the selected range of received likes
    - Quantity of Comments: Show all posts within the selected range of received comments
    - Quantity of Reposts: Show all posts within the selected range of received reposts.
- General Search: 
    - Search by Post Title: Enter the title of the post to get information about the posts with the same title: post ID, likes, comments and number of retweets.
- Advanced Search:
    - Search by Post ID: Enter the title of the post to get information about the post with the same id: title, likes, comments and number of retweets.
    - Search by Tag ID: Enter the tag id and get information (title, likes, comments and number of retweets) about the post with that tag, if not, return "no result"     
    - Search by Tag Name: Enter the tag name and get information (title, likes, comments and number of retweets) about all the posts with that tag, if not, return "no result" 
        - There may be many posts with the same tag name.
- Edit Account Info:
    - Enter post id: Just Enter post's id.
    - Edit post title: Submit new title of this post.
    - Edit tag name: submit new tag name, if it's not the image-only post return "We cannot do this."
    - Delete Posts: Delete the posts and all the info that belong to it.
#