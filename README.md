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
# Use default: nginx redis mysql 
laradock up -d
# Or by yourself
laradock up nginx mysql

# Stop containers
laradock stop

# Enter workspace
laradock workspace
# Or use root
laradock workspace --root

# List all containers
laradock ps

```

# License
Apache License
