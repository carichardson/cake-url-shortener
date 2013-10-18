cake-url-shortener
==================

### CakePHP Url Shortener

LAMP based URL shortener demonstrable at [chrisrichardson.ca](http://chrisrichardson.ca).  Enter a url and your desired short code to generate a shortened url (or not, just had chrisrichardson.ca lying around...).

<strong>Example short url</strong>: [chrisrichardson.ca/beer](http://chrisrichardson.ca/beer) should take you to a legendary beer blog.

You can also make use of the handy bookmarklet to shorten urls as you browse the web, or checkout click stats [here](http://chrisrichardson.ca/stats).

### Tests

Cake provides light integration with PHPUnit, which I used to create some rudimentary tests.  You can run those tests [here](http://chrisrichardson.ca/test.php).

### API

API Endpoint: http://chrisrichardson.ca/api/url

<strong>Action:</strong> shorten_url  

<strong>Description:</strong> Post a long url to receive a shortened url.  

<strong>Method:</strong> POST  

<strong>Required Parameter:</strong> url - a valid url to shorten  


Sample response:

```
	{
		"success"	: true,
		"url"		: "chrisrichardson.ca/44k2ug"
	}
```

Sample PHP code:

```
	$data = array(
		'action' => 'shorten_url',
		'url' => 'http://supertesturl.com'
	);
	$ch = curl_init('http://chrisrichardson.ca/api/url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
```

<strong>Action:</strong> retrieve_url  

<strong>Description:</strong> Post a short code to receive the long url.  

<strong>Method:</strong> POST  

<strong>Required Parameter:</strong> code - code for a shortened url  


Sample Response:

```
	{
		"success"	: true,
		"url"		: "http://lovegoodbeer.com/2013/09/zwanze-day-2013-at-alibi-room/"
	}
```

Sample PHP code:

```
	$data = array(
		'action' => 'retrieve_url',
		'code' => 'beer'
	);
	$ch = curl_init('http://chrisrichardson.ca/api/url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$response = json_decode(curl_exec($ch), true);
	curl_close($ch);
```

### ZeroMQ

I got ZeroMQ built and working with PHP, but ran out of time with respect to getting it working correctly with the API.  You can see my initial attempts in app/Controller/ApiController.php.