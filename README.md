Deploy:
    
    Install composer, symfony cli tools:
        https://getcomposer.org/download/
        https://symfony.com/download

    Install dependencies:
        composer install
    
    Generate the SSH keys for jwt authentication:
    !!!Enter pass phrase: 8952557e6a3f2726f385ee40a131eb8c or rewrite JWT_PASSPHRASE in .env.loc
        mkdir -p config/jwt
        openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
        openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    
    Setup dev database:
        docker-compose up -d
    
    Update database schema, add test data:
        symfony console doctrine:migrations:migrate -n
        symfony console doctrine:fixtures:load -n
        
    Start server:
        symfony server:start -d

Get auth token:
    
    For Admin:
        curl --location --request POST 'https://127.0.0.1:8000/api/login' \
        --header 'Content-Type: application/json' \
        --data-raw '{
          "username": "admin",
          "password": "admin"
        }'
    
    For player:
        curl --location --request POST 'https://127.0.0.1:8000/api/login' \
        --header 'Content-Type: application/json' \
        --data-raw '{
          "username": "kirk",
          "password": "kirk"
        }'

Make spin:

    curl --location --request POST 'https://127.0.0.1:8000/api/spins' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer PUT_PLAYERS_TOKEN_HERE' \
    --data-raw '{
      "bids": [{"user": "2", "cell": "1"}, {"user": "3", "cell": "5"}]
    }'

Player statistics:

    curl --location --request GET 'https://127.0.0.1:8000/api/statistics/players' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer PUT_ADMIN_TOKEN_HERE'

Rounds statistics:

    curl --location --request GET 'https://127.0.0.1:8000/api/statistics/rounds' \
    --header 'Content-Type: application/json' \
    --header 'Authorization: Bearer PUT_ADMIN_TOKEN_HERE'