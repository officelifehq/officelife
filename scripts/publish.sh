#!/bin/bash

set -eo pipefail

version=$1
if [ "$version" == "" ]; then
  echo "Version parameter is mandatory" >&2
  exit 1
fi

file=$2
if [ "$file" == "" ]; then
  echo "File parameter is mandatory" >&2
  exit 1
fi

set -v

if [ -z "$GITHUB_TOKEN" ]; then
  gh auth login
fi

for f in $(ls -d $file*); do
  gh release upload "$version" "$f" --clobber
done
