# InternshipProject
git clone https://github.com/paweu9000/InternshipProject.git

# Run in terminal
cd LibraryBackend
mkdir config/jwt

# Generate private key for jwt token
openssl genpkey -algorithm RSA -out config/jwt/private.pem -pkeyopt rsa_keygen_bits:2048 && openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

docker compose up
