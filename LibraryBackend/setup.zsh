#!/bin/zsh

JWT_DIR="./config/jwt"

if [ -d "$JWT_DIR" ]; then
    echo "'$JWT_DIR' already exists"
else
    mkdir -p "$JWT_DIR"
fi

PRIVATE_KEY="$JWT_DIR/private.pem"
PUBLIC_KEY="$JWT_DIR/public.pem"

if [ -f "$PRIVATE_KEY" ]; then
    echo "Private key already exists: $PRIVATE_KEY"
else
    openssl genpkey -algorithm RSA -out "$PRIVATE_KEY" -pkeyopt rsa_keygen_bits:2048
    if [ $? -eq 0 ]; then
        echo "Private key generated: $PRIVATE_KEY"
    else
        echo "Failed to generate the private key."
        exit 1
    fi
fi

if [ -f "$PUBLIC_KEY" ]; then
    echo "Public key already exists: $PUBLIC_KEY"
else
    openssl rsa -pubout -in "$PRIVATE_KEY" -out "$PUBLIC_KEY"
    if [ $? -eq 0 ]; then
        echo "Public key generated: $PUBLIC_KEY"
    else
        echo "Failed to generate the public key."
        exit 1
    fi
fi

# For some reason jwt folder cant be accessed on docker image 
# after starting container without adding permissions when using linux
# this fixes it
chmod -R 777 "$JWT_DIR"

docker-compose up