#!/usr/bin/env bash
# Télécharge Bootstrap (CSS + JS) en local dans kiko/css et kiko/js, pour ne plus
# dépendre du CDN cdn.jsdelivr.net sur les pages qui le chargent (index.php,
# sync_all.php, presences.php, new.php, saisons.php).
#
# À exécuter à chaque changement de version de Bootstrap (BOOTSTRAP_VERSION
# ci-dessous), avant publication.
#
# Usage: ./scripts/update-bootstrap.sh

set -euo pipefail

BOOTSTRAP_VERSION="5.3.3"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
KIKO_DIR="$(dirname "$SCRIPT_DIR")"
CDN_BASE="https://cdn.jsdelivr.net/npm/bootstrap@${BOOTSTRAP_VERSION}/dist"

fetch() {
    local url="$1"
    local dest="$2"
    echo "Téléchargement de ${url}"
    curl -fsSL "$url" -o "$dest"
    echo "  -> ${dest} ($(wc -c < "$dest" | tr -d ' ') octets)"
}

fetch "${CDN_BASE}/css/bootstrap.min.css" "${KIKO_DIR}/css/bootstrap.min.css"
fetch "${CDN_BASE}/css/bootstrap.min.css.map" "${KIKO_DIR}/css/bootstrap.min.css.map"
fetch "${CDN_BASE}/js/bootstrap.bundle.min.js" "${KIKO_DIR}/js/bootstrap.bundle.min.js"
fetch "${CDN_BASE}/js/bootstrap.bundle.min.js.map" "${KIKO_DIR}/js/bootstrap.bundle.min.js.map"

echo "Bootstrap ${BOOTSTRAP_VERSION} installé en local."
