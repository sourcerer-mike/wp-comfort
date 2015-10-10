#!/usr/bin/env bash

baseDir=$(git rev-parse --show-toplevel)

wpContent=${wpContent-wp-content}

for D in $(ls -1 ${wpContent}/plugins); do
	TARGET=${wpContent}/plugins/$D

	if [[ ! -d $TARGET ]]; then
		continue;
	fi

	if [[ $D == cx-* ]]; then
		continue;
	fi

	postMessage="installed"
    if [[ "" != "$(git ls-files $TARGET)" ]]; then
        postMessage="updated";

        preVersion="";
        postVersion="";

        if [[ -f ${TARGET}/${D}.php ]]; then
            postVersion=$(grep "Version:" ${TARGET}/${D}.php | cut -d" " -f2)
            preVersion=$(git show HEAD:${TARGET}/${D}.php | grep "Version:" | egrep -o "[0-9\.]+")

            postMessage="$postMessage from $preVersion to $postVersion"
        fi
    fi

	git add --all $TARGET > /dev/null;
	git commit -m "Plugin $D $postMessage" ${TARGET};
done