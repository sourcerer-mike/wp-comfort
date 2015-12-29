Feel free to fork and send merge requests.

## Releases

Are made using semantic versions.

### Major

Those releases are incompatible with their previous.
Migration is only guaranteed from one specific minor version.

- Doc: There should be an API documentation.
- PHPMD should not have any errors.
- PHPUnit Code Coverage should be over 90% in Methods and Lines.

And everything from minor releases.

### Minor

Such releases are compatible with their previous minor version.

Assertions before the release:

- `git branch -a --no-merge` should not contain features that are meant for the release.
- PHPMD should not have any warnings.
- PHPUnit Code Coverage should be over 90% in Lines.
- PHPUnit should have no incomplete tests.
- PHPSemVer should not show any major change.
- PHPCPD should not find duplicate code.

And everything from patches.

### Patch

This harms no one.

- PHPUnit Code Coverage should be over 80% in Lines.
- PHPUnit should have no errors.
- PHPSemVer should not show any minor change.