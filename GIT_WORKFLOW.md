# Git Workflow & Branch Sync Guide

This document describes the recommended Git workflow for the Inventory Management project: how to keep `main` (frontend) and `backend` branches in sync, and when to merge.

## Branch Strategy

- **main**: Frontend-only (static HTML/CSS/JS). No backend code. Always stable and production-ready for deployment.
- **backend**: Backend API (Laravel in `backend/` folder). All backend work happens here.
- **feature branches** (optional): If multiple backend developers, branch off `backend` for new features, then PR into `backend`.

## Workflow: Frontend and Backend Teams

### 1. Backend Team: Develop on `backend` branch

```bash
git checkout backend
# create feature branches off backend if needed
git checkout -b backend/feature-xyz

# commit and push
git push origin backend/feature-xyz

# PR/merge back to backend
# (After approval, merge via GitHub PR or local merge-commit)
git checkout backend
git pull origin backend
git merge backend/feature-xyz
git push origin backend
```

### 2. Frontend Team: Develop on `main` branch (no backend changes)

```bash
git checkout main
# work on HTML/CSS/JS in frontend/ folder and root html files
git commit -m "frontend: add login form"
git push origin main
```

### 3. Sync Backend Changes into Main (when ready)

When backend features are tested and ready for the main site, merge `backend` into `main`:

```bash
# On your local main
git checkout main
git pull origin main
git pull origin backend    # or: git merge origin/backend

# Verify no conflicts
# If conflicts: resolve, test, then commit the merge
git commit -m "merge backend features into main"
git push origin main
```

### 4. Keep Backend Updated with Main Changes (optional)

If frontend team makes changes to shared files (e.g., `.env.example`, `docker-compose.yml`), backend branch should pick them up:

```bash
git checkout backend
git pull origin main    # or: git merge origin/main
git push origin backend
```

---

## Tips

- **Test before merging**: After merging backend into main, run the full stack (Docker Compose) and test end-to-end.
- **Use PRs on GitHub**: Create pull requests between branches for review and discussion before merging.
- **Tag releases**: After merging into main, tag a release:
  ```bash
  git tag -a v1.0.0 -m "Release 1.0.0"
  git push origin v1.0.0
  ```
- **Rebase or merge?**: This guide uses merge-commits for clarity; if you prefer rebase-based history, adjust accordingly.

## Deployment

- **Frontend (main branch)**: Deploy static files from `frontend/` and root HTML files to web server or CDN.
- **Backend (backend branch)**: Deploy Laravel app from `backend/` to a PHP hosting or container service. Use migrations and `.env` to configure DB credentials per environment.

For Docker deployments, both branches can live in the same repo; use `docker-compose.yml` to run both locally during development.
