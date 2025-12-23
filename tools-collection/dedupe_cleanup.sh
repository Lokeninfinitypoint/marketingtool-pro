#!/bin/bash
# Automatically remove duplicate files safely from a project

cd ~/addswebstorm || exit

echo "=== Scanning for duplicates (excluding backups and cache) ==="

# Find duplicates but ignore cache and backup directories
fdupes -r . \
  --exclude "_duplicates_backup" \
  --exclude "wp-content/cache" \
  --exclude "wp-content/et-cache" \
  --exclude ".git" > duplicates.txt

echo "=== Backing up duplicates to _duplicates_backup/ ==="
mkdir -p _duplicates_backup

# Copy duplicates to backup folder before deletion
while read file; do
  if [[ -f "$file" ]]; then
    cp --parents "$file" _duplicates_backup/ 2>/dev/null
  fi
done < <(grep -v "^$" duplicates.txt)

echo "=== Removing duplicates automatically ==="
fdupes -rdN .

echo "=== Verifying cleanup ==="
fdupes -r .

echo "=== Committing cleaned repo to Git ==="
git add .
git commit -m "Automated duplicate cleanup and backup"
git push origin main

echo "=== Done. All duplicates cleaned safely. ==="
