# Release Script Automation

Use this helper script from package root:

```bash
./scripts/release-packagist.sh v0.0.15 "your commit message"
```

What it does:
- validates semantic version tag format (`vX.Y.Z`)
- stages all changes
- creates a commit (if needed)
- pushes `main`
- creates and pushes the tag

After script completes, if Packagist webhook is delayed, click **Update** from package page.
