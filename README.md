# laradock-cli
A small commandline tool to create, start, stop laradock, or enter the workspace

# Install

`composer global require lorinlee/laradock-cli`

# Usage
```
# Enter the laravel project directory

# Create laradock
laradock init

# Start containers
laradock up nginx mysql redis

# Stop containers
laradock stop nginx 
# Or stop all containers
laradock stop

# Enter workspace
laradock bash
# Or use root
laradock bash --root

# List all containers
laradock ps

# Edit docker-compose.yml
laradock config

```

# License
Apache License
