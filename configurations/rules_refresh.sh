# This script is called by openHAB after persistance service was started
sleep 5
cd /opt/openhab/configurations/rules/
RULES=`find *.rules | grep -v refresh.rules`
for f in $RULES
do
  touch $f
done 