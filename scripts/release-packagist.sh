#!/usr/bin/env bash
set -euo pipefail

if ! command -v git >/dev/null 2>&1; then
  echo "git is required" >&2
  exit 1
fi

if [[ $# -lt 2 ]]; then
  echo "Usage: $0 <version> <commit-message>"
  echo "Example: $0 v0.0.15 \"ui: refine mode toggle and timer icons\""
  exit 1
fi

VERSION="$1"
shift
COMMIT_MSG="$*"

if [[ ! "$VERSION" =~ ^v[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Version must be semantic and prefixed with v (example: v0.0.15)" >&2
  exit 1
fi

REPO_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$REPO_ROOT"

if [[ -n "$(git status --porcelain)" ]]; then
  echo "==> Staging all changes"
  git add .

  echo "==> Creating commit"
  git commit -m "$COMMIT_MSG"
else
  echo "No working tree changes found."
fi

if git rev-parse "$VERSION" >/dev/null 2>&1; then
  echo "Tag $VERSION already exists locally." >&2
  exit 1
fi

if git ls-remote --tags origin | grep -q "refs/tags/$VERSION$"; then
  echo "Tag $VERSION already exists on origin." >&2
  exit 1
fi

echo "==> Pushing main"
git push origin main

echo "==> Creating tag $VERSION"
git tag "$VERSION"

echo "==> Pushing tag $VERSION"
git push origin "$VERSION"

echo "Done."
echo "Next: open Packagist package page and click 'Update' if webhook is delayed."
