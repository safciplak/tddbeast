# Browser Testing vs. Endpoint Testing

## Browser Testing

Using a tool like Selenium or PhantomJs to simulate the user's actions inside
the browser.


```php
$this->type('#tilte', 'My first blog post');
$this->type('#body', 'Lorem ipsum');
$this->submitForm('Add post');
```

### Pros

- Simulates exactly how a user would interact with the application
- Gives you complete confidence that the application is working end-to-end

### Cons

- Need to introduce a new tool to our stack(Selenium, PhantomJs, etc) if you
need to execute Javascript
- Slower
- More brittle, can often break due to important changes in the UI
- Complex to setup
- Often can't interact with code directly, need to make assertions trough the
UI

## Endpoint Testing

Making Http requests directly to and endpoint, simulating how the browser would 
interact with oru server instead of how the user would interact with our app.


```php
$this->post('/posts', [
    'title' => 'My first post',
    'body' => 'Lorem ipsum',
]);
```


### Pros

- Faster
- Does not require any additional tooling
- Interacting with more stable data structures won't break when changes are
made for aesthetic reasons
- Can intereact directly with code, more flexible assertions

### Cons

- Untested gap between front-end and back-end

## What do I want from my tests?

1. Confidence that the system work  
2. Reliable, don't break from unimportant reasons
3. Fast, so I run then often
4. Simple, as few tools as possible, easy to recreate test environment