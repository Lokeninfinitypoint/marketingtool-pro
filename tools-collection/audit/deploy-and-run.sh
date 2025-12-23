#!/usr/bin/env bash
# Deploy audit scripts to server and run comprehensive audit.
# Usage on your LOCAL machine: ./deploy-and-run.sh
# Or manually copy scripts and run on server.
set -euo pipefail

SSH_HOST="u520004865@77.37.90.129"
SSH_PORT="65002"
WP_ROOT="domains/adswebstrom.com/public_html"

echo "=== DEPLOYING AUDIT TOOLKIT ==="
echo "Copying scripts to server..."

# Upload scripts
scp -P "$SSH_PORT" site_audit.sh diag-report.php wpcli-link-check.php README.md "$SSH_HOST:$WP_ROOT/"

echo "=== RUNNING AUDIT ON SERVER ==="
ssh -p "$SSH_PORT" "$SSH_HOST" << 'ENDSSH'
cd domains/adswebstrom.com/public_html
chmod +x site_audit.sh
./site_audit.sh "$(pwd)"
echo ""
echo "=== RUNNING LINK CHECK ==="
wp eval-file wpcli-link-check.php > link-check.txt
echo "Link check saved to link-check.txt"
echo ""
echo "=== FETCHING REPORTS ==="
ls -lh audit-report-*.txt link-check.txt
ENDSSH

echo ""
echo "=== DOWNLOADING REPORTS ==="
scp -P "$SSH_PORT" "$SSH_HOST:$WP_ROOT/audit-report-*.txt" "$SSH_HOST:$WP_ROOT/link-check.txt" . || true

echo ""
echo "✓ Complete! Check audit-report-*.txt and link-check.txt locally."
