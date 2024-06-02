# Test PHP

## Subject
This web application web allow you to manage articles with api or front. Think of it as the start of a large application that will evolve over time. Each element must be designed to be able to evolve, be easily maintained and perform well.

### Rules
An article must include at least the following elements:  
- Title (text - max 128 characters)
- Content (text)
- Author (user)
- Release date (date and time)
- Status = draft, published, deleted

If the status is set to "published", you cannot enter a publication date, as it is automatically set to today.  
If you change the status to "draft", you can optionally enter a (future) publication date.  
When an article is deleted, we keep data in database.

The app has a validation system and must be respected:
- It must be possible to create an article (with draft or published status only)
- It must be possible to modify an article (only if it is in draft status)
- An article must be able to be moved from draft to published or deleted
- It must be possible to change the status of an article from published to deleted or draft
- A deleted article is no longer accessible
- There must be a way of listing draft and published articles (deleted articles must never appear in the API).
- Only author can edit an article

### A few additional notes and constraints
- You are free to use the implementation of your choice but keep in my mind that you will be asked to justify this choice.  
- You must not use a tool/framework that allows you to generate an API almost without coding (API Platform, for example). That's right! The point is to see how you code 🙂.  
- Any additional functionality will be appreciated (rights management, search, pagination, test data, installation script, html/markdown format in articles, ...).  
- There are no constraints on modules, database, libraries, tools, etc.

### Assessment
The assessment will focus mainly on 3 points:
 - The quality of the code and the fact that there is no redundancy or unnecessary code (the code must be easy to read without comment)
 - The choice of technology and tools, which must be justified at the end of the test
 - The fact that there is no (or very little) business logic in the controllers, but that this is deported to specialised departments, for example
