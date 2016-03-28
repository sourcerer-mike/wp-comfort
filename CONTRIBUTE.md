Feel free to fork and send merge requests.

## Releases

Are made using semantic versions.

### Major

Those releases are incompatible with their previous.
Migration is only guaranteed from one specific minor version.

- Look up all `@deprecated` functions and remove them.
- Doc: There should be an API documentation.
- PHPUnit: Code Coverage should be over 90% in Methods and Lines.

And everything from minor releases.

### Minor

Such releases are compatible with their previous minor version.

Assertions before the release:

- `git branch -a --no-merge` should not contain features that are meant for the release.
- PHPUnit Code Coverage should be over 90% in Lines.
- PHPUnit should have no incomplete tests.
- PHPSemVer should not show any major change.
- The support-branch should have a description (`git branch --edit-description`) with a change log,
  upgrade and downgrade notices to the nearest minor or major version.

And everything from patches.

### Patch

This harms no one.

- PHPUnit Code Coverage should be over 80% in Lines.
- PHPUnit must have no errors
- PHPSemVer must not show any minor change.
- The version-tag must contain git-notes with a change log (`git flow` helps).
  You may enumerate the changes using the [semantic commit] (https://gist.github.com/sourcerer-mike/9629666).
  Hint: A diff of the phpunit testdox might help.
- Some support-branch description should be updated:
	- The next lower support-branch should contain upgrade notices.
	- The next higher support-branch should contain downgrade notices.
	- The support-branch description of the minor version should be extended by the change log.
