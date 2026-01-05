# Script para reconstruir rápidamente Docker después de cambios
Write-Host "Reconstruyendo contenedor..." -ForegroundColor Green
docker-compose build app
docker-compose up -d app
Write-Host "Listo! Aplicación actualizada" -ForegroundColor Green
