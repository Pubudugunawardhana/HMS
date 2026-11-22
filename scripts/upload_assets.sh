#!/usr/bin/env bash
# One-time: copy existing images/ and uploads/ into Vercel Blob at the same paths.
# Usage: BLOB_READ_WRITE_TOKEN=vercel_blob_rw_xxx bash scripts/upload_assets.sh
set -euo pipefail
: "${BLOB_READ_WRITE_TOKEN:?set BLOB_READ_WRITE_TOKEN}"
cd "$(dirname "$0")/.."
find images uploads -type f | while read -r f; do
  case "$f" in
    *.jpg|*.jpeg) t=image/jpeg ;; *.png) t=image/png ;; *.webp) t=image/webp ;;
    *.svg) t=image/svg+xml ;; *.pdf) t=application/pdf ;; *) t=application/octet-stream ;;
  esac
  code=$(curl -s -o /dev/null -w "%{http_code}" -X PUT \
    "https://vercel.com/api/blob/?pathname=$(printf %s "$f" | sed 's#/#%2F#g')" \
    -H "authorization: Bearer $BLOB_READ_WRITE_TOKEN" \
    -H "x-api-version: 11" -H "x-vercel-blob-access: public" \
    -H "x-add-random-suffix: 0" -H "x-allow-overwrite: 1" \
    -H "x-content-type: $t" --data-binary @"$f")
  echo "$code $f"
done
