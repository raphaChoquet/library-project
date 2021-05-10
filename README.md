# Library project

## Getting start

Go to the folder `./deploy` and execute the commande `make init` to initialize the project.

## API Documentation

### URL

The api is available from the url `http://localhost:8080`.

## Authentification 

We use JWT authentification to protect the API.
All resources ask a valid JWT token. 
Use the url `/api/auth` to retrieve this token thanks to this accounts:

***Admin account***:
Login: admin
Password: admin

***User account***:
Login: toto
Password: toto

For more information about JWT : 
- https://jwt.io/introduction/
- https://github.com/lexik/LexikJWTAuthenticationBundle

## Documentation

Documentation api is available [here](http://localhost:8080/api/doc)


