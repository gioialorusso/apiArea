# apiArea
Simple REST APIs to calculate areas.

## Getting Started
After cloning the project, you have to run 
```
composer install
```
to install all the dependencies.

## Running the tests
Before running the testsuite you should set your own base address into the variable
```
$GLOBALS['base_url_address']
```
into the config_local.php file. It is used by the HttpClient 
to call the APIs and check for the returned value.
Then you can run the testsuite with 
```
vendor/bin/phpunit --bootstrap tests/bootstrap.php tests/
```

## Implemented endpoints
In POST request, the required Content-Type is application/json. The body must be json encoded.

**Get Circle Area**
* **URL**
  /area/circle/:radius
* **Method:**
  `GET`
*  **URL Params**
   * **Required:**
   `radius`
* **Querystring Params**
  None
  
* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{"result": "OK","output": 28.274,"debug_info": {"error_msg": null,"shape": "circle","radius": "3"}}`
 
* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Reason:** If radius is negative or non-numeric
    
    
**Get Square Area**
* **URL**
  /area/square
* **Method:**
  `GET`
*  **URL Params**
   None
* **Querystring Params**

  * **Required**
  `side`
* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{"result": "OK","output": 16,"debug_info": {"error_msg": null,"shape": "square","side": "4"}}`
 
* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Reason:** If side is negative or non-numeric
   
   
**Get Rectangle Area**
* **URL**
  /area/rectangle
* **Method:**
  `POST`
*  **URL Params**
   None
*  **Querystring Params**
   None
* **JSON Body Params**

  * **Required**
  `{"base": x, "height": y}`
  
* **Headers Params**
  `Content-Type: application/json`
* **Success Response:**

  * **Code:** 200 OK<br />
    **Content:** `{    "result": "OK",    "output": 20,    "debug_info": {        "error_msg": null,        "shape": "rectangle",        "base": "4",        "height": "5"    }}`
 
* **Error Response:**

  * **Code:** 400 BAD REQUEST <br />
    **Reason:** If base or height are negative or non-numeric
