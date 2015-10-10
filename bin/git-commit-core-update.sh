#!/usr/bin/env bash

wpContent=${wpContent-wp-content}

# List of WordPress core files
TARGETS=(
	wp-activate.php
	wp-admin/
	wp-blog-header.php
	wp-comments-post.php
	wp-config-sample.php
	${wpContent}/index.php
	${wpContent}/languages
	${wpContent}/plugins/index.php
	${wpContent}/themes/index.php
	${wpContent}/themes/twentyeleven
	${wpContent}/themes/twentytwelve
	${wpContent}/themes/twentythirteen
	${wpContent}/themes/twentyfourteen
	${wpContent}/themes/twentyfifteen
	${wpContent}/themes/twentysixteen
	wp-cron.php
	wp-includes/
	wp-links-opml.php
	wp-load.php
	wp-login.php
	wp-mail.php
	wp-register.php
	wp-settings.php
	wp-signup.php
	wp-trackback.php
	xmlrpc.php
);

removeFiles=(
	license.txt
	liesmich.html
	README
	readme.html
	wp-config-sample.php
);

# Clean-up staged files
git reset HEAD -- . > /dev/null

status=$?

if [ ${status} -ne 0 ]; then
	echo "ERROR: Could not reset the branch to HEAD."
fi

# Add if exists
for T in "${TARGETS[@]}"; do
	if [[ ! -d ${T} && ! -f ${T} ]]; then
		continue;
	fi
	
	# echo Adding $T
	git add --all $T
done

for singleFile in "${removeFiles[@]}"; do
	if [[ ! -f $singleFile ]]; then
		continue;
	fi

	git rm -rf $singleFile &> /dev/null
done

previousVersion=$(git show HEAD:wp-includes/version.php | grep "wp_version =" | egrep -o "[0-9\.]+")
currentVersion=$(cat wp-includes/version.php | grep "wp_version =" | egrep -o "[0-9\.]+")

git commit -m 'WordPress Core Update ${previousVersion} to ${currentVersion}'

echo "Core Update has been commited."
echo ""
echo "To see what is not yet in the commit type:"
echo "	git diff --name-only"
echo ""
echo "To append such files use"
echo "	git commit --amend FILENAME.1 FILENAME.2 DIRECTORY.42"
echo ""