#!/usr/bin/env bash
#.git/hooks/post-merge

# git composer hook to run a command after `git pull` or `git checkout` if composer.lock file was changed
# Run `chmod +x post-checkout` to make it executable then put it into `.git/hooks/`.
echo ".......................PRE CHECKOUT/PULL TASKS......................."

changed_files="$(git diff-tree -r --name-only --no-commit-id HEAD@{1} HEAD)"

check_run() {
	echo "$changed_files" | grep --quiet "$1" && echo " * changes detected in $1" && echo " * running $2" && eval "$2"
}

if [ -f "composer.lock" ]
then
    php composer.phar update
    echo '---- checking composer'
	check_run composer.lock "php composer.phar install"
fi
