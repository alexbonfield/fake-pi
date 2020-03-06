# fake-pi

This app mimics the behaviour very crudely of the API for adding and removing pending devices. Useful for local development.

# How to:
Create an .env in the root directory, see .env.example for the variables you will need. The host will be the name of the docker container for the CP database, usually, careplanner_db_1. Username and password you can see in the CP docker-compose file.
Once you've done this, you need to go to the docker-compose file in the CP project and change the API URL variable to http://172.20.0.3 and do make up.
Run make up in the fake-pi directory and it should build the containers and connect them.
