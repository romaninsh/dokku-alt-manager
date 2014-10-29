#!/bin/bash -e

# Read versio
version=`cat VERSION`
export product="dokku-alt-manager"

# Clean up
rm -rf dist
mkdir -p dist/$product
#mkdir -p dist/agiletoolkit/atk4-ide

# Copy some stuff inside
cp -aR vendor shared addons readme.md dist/$product/
cp -aR admin VERSION dist/$product/

cp -a config-default.php dist/$product/
cp -a index.php dist/$product/
cp -a config-dist.php dist/$product/config.php

# patch up atk4 symlinks
[ -s vendor/atk4/atk4 ] && { rm dist/$product/vendor/atk4/atk4; cp -aR ~/Sites/atk43 dist/$product/vendor/atk4/atk4; }

cp gitignore-distrib dist/$prduct/.gitignore
cp composer.json dist/$product/
cp composer.lock dist/$product/

# Strip group write permssions as it makes people upset
( cd dist; chmod -R g-w $product )

echo "cleaning .git from dist"

( cd dist && find .  -name .git | while read x; do rm -r $x; done )

# tar, --no-xattrs
( cd dist; tar --no-same-owner  -czf ${product}-${version}.tgz ${product} )

#  ${product}/ && cp ${product}-${version}.tgz /www/${product}.org/public/dist/ && \
#  cp ${product}/${product}-sandbox.phar /www/${product}.org/public/dist/ )

exit;

rm -rf dist/$product

# next , let's upload file to the server

rm -rf /www/install-test/agiletoolkit/
(
  cd /www/install-test/;
  tar -zxf /www/agiletoolkit.org/public/dist/agiletoolkit-${version}.tgz
  sudo chgrp upload -R .
)

# give rights so others can do it too
chmod -R g+w /www/install-test/agiletoolkit/

echo "Installed. Archive is http://www4.agiletoolkit.org/dist/agiletoolkit-${version}.tgz "
echo "Test: http://install-test-399482.agiletoolkit.org/agiletoolkit/"
