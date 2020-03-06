up :
	docker-compose build; \
	docker-compose up -d; \
	docker network connect fake-pi_api_network careplanner_careplanner_1; \
	docker network connect fake-pi_api_network careplanner_db_1
down :
	docker network disconnect fake-pi_api_network careplanner_careplanner_1; \
	docker network disconnect fake-pi_api_network careplanner_db_1; \
	docker-compose down
