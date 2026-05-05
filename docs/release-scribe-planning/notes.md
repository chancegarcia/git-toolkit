# commands

## get first commit

    git rev-list --max-parents=0 HEAD

## first commit to tag

    git log --format="- %B" --no-merges <commit id>..<tag name>

example:

    git log --format="- %B" --no-merges c595bfc6151e81295af49552bc322d0b10c9efae..4.0.0

## commits from tag to now

    git log --format="- %B" --no-merges <tag name>..HEAD

# Resources

- https://medium.com/better-programming/create-your-own-changelog-generator-with-git-aefda291ea93-aefda291ea93
- https://git-scm.com/docs/git-log#_pretty_formats