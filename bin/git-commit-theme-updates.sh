#!/usr/bin/env bash

baseDir=$(git rev-parse --show-toplevel)

wpContent=${wpContent-wp-content}

for D in $(ls -1 ${wpContent}/themes); do
	TARGET=${wpContent}/themes/${D}

	if [[ ! -d $TARGET ]]; then
		continue;
	fi

	if [[ $D == cx-* ]]; then
		continue;
	fi

	git add --all $TARGET > /dev/null;
	git commit -m "Theme aktualisiert - $D" $TARGET;
done