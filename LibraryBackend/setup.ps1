$configPath = "./config"
$jwtFolderPath = Join-Path -Path $configPath -ChildPath "jwt"
$privateKeyPath = Join-Path -Path $jwtFolderPath -ChildPath "private.pem"
$publicKeyPath = Join-Path -Path $jwtFolderPath -ChildPath "public.pem"

if (-Not (Test-Path -Path $jwtFolderPath)) {
    New-Item -ItemType Directory -Path $jwtFolderPath -Force
    Write-Host "JWT folder created at '$jwtFolderPath'."
} else {
    Write-Host "JWT folder already exists at '$jwtFolderPath'."
}

if (-Not (Test-Path -Path $privateKeyPath)) {
    try {
        $generatePrivateKey = "openssl genpkey -algorithm RSA -out $privateKeyPath -pkeyopt rsa_keygen_bits:2048"
        Invoke-Expression $generatePrivateKey
    } catch {
        Write-Host "An error occurred during private key generation: $_" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "Private key already exists at '$privateKeyPath'."
}

if (-Not (Test-Path -Path $publicKeyPath)) {
    try {
        $generatePublicKey = "openssl rsa -pubout -in $privateKeyPath -out $publicKeyPath"
        Invoke-Expression $generatePublicKey
    } catch {
        Write-Host "An error occurred during public key generation: $_" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "Public key already exists at '$publicKeyPath'."
}

try {
    docker-compose up
} catch {
    Write-Host "An error occurred while running 'docker-compose up': $_" -ForegroundColor Red
    exit 1
}