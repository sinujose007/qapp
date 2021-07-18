# qapp - Simple REST App

REST API implimentation using PHP 7

-----
## Table of Contents

* [Features](#item1)
* [Quick Start](#item2)

-----
<a name="item1"></a>
## Features:
* REST API END POINTS
  * Get all the records using API
  * Post a Single Record using API.
-----
<a name="item2"></a>
## Quick Start:

Clone this repository and install the dependencies.

    $ git clone https://github.com/sinujose007/qapp.git 
    
To get records , Call get method api with below end point 
	http://127.0.0.1/qapp/public/questions?lang=ar
	http://127.0.0.1/qapp/public/questions?lang=sp , etc.
	
To Store record , Call POST method API with below  end point
	http://127.0.0.1/qapp/public/questions
	
Two database source files are used in the system json and csv.
You can change the config.php to select different data sources.

-----
