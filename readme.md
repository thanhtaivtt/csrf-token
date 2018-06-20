# CSRF Token class
## Usage
- Init class
```php
// init class
$csrf = new CSRF();
//or
$csrf = CSRF::init();
```
- Get token
```php
// get token value
$csrf->getToken() // output: 4607dd1f13b2b92370c48e9b7ea90feb

```
- Get token field:
```php
$csrf->getInput() // output: <input type="hidden" name="_token" value="4607dd1f13b2b92370c48e9b7ea90feb">

```
- Validate request
```php
$ss->validate() // return true if valid
```
## Notes
- This class only works when the server configuration ENV variable `_PROTECT_CSRF = true` 