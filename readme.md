# Git Toolkit

[![Build Status](https://travis-ci.com/chancegarcia/git-toolkit.svg?branch=main)](https://travis-ci.com/chancegarcia/git-toolkit) [![Latest Stable Version](https://poser.pugx.org/chancegarcia/git-toolkit/v)](//packagist.org/packages/chancegarcia/git-toolkit) [![Total Downloads](https://poser.pugx.org/chancegarcia/git-toolkit/downloads)](//packagist.org/packages/chancegarcia/git-toolkit) [![Latest Unstable Version](https://poser.pugx.org/chancegarcia/git-toolkit/v/unstable)](//packagist.org/packages/chancegarcia/git-toolkit) [![License](https://poser.pugx.org/chancegarcia/git-toolkit/license)](//packagist.org/packages/chancegarcia/git-toolkit)

develop branch:

[![Build Status](https://travis-ci.com/chancegarcia/git-toolkit.svg?branch=develop)](https://travis-ci.com/chancegarcia/git-toolkit)

---

This toolkit only contains one tool currently. That tool will create a `changelog.md` for a project using the git
repository tags and the git commit history.

## Installation

        composer require chancegarcia/git-toolkit

## ChangeLog Command Usage

        bin/toolkit toolkit:changelog

The changelog file will produce a **markdown** document with a main header (optionally set by the header argument)
of `Projecty McProjectFace`. Tag names are the subheaders and the commits between the tags are printed after the
subheader

While you can run the base command, without a header argument, to produce a changelog, you might want to pass the a main
header argument with it. Unless you really want to have the top header be `Projecty McProjectFace` (totally not judging)
.

        bin/toolkit toolkit:changelog "I am not a cat."

### Prepare a new or initial release tag/number

If no tags are present. The subheader will be the commit id.

In order to "create" a new tag, use the `--new-tag=<NEWTAG>` to set the new header and write all recent commits since
the previous tag (if there is one) into the changelog.

        bin/toolkit toolkit:changelog "We Love Kittens" --new-tag="1.0.0"
