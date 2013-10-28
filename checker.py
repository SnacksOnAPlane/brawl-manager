#!/usr/bin/python

import subprocess
import datetime
from pytz import timezone
import pytz

def getOnlinePis():
  output, stderr = subprocess.Popen("/usr/local/bin/lspis | tail -n +1 | awk {'print $4'}", shell=True, stdout = subprocess.PIPE, stderr = subprocess.PIPE).communicate()
  retme = []
  for line in output.split("\n"):
    if not line:
      continue
    ip, port = line.split(":")
    port = int(port) - 8000
    retme.append(port)
  return retme

def getLastOnlinePis():
  retme = []
  with open("/tmp/online_pis") as f:
    for line in f:
      retme.append(int(line))
  return retme

def writeLastOnlinePis():
  with open("/tmp/online_pis", "w") as f:
    for pi in online_pis:
      f.write("%s\n" % (pi))

def statusChanged(hotspot_id):
  if hotspot_id in last_online_pis and not (hotspot_id in online_pis):
    return True
  if hotspot_id in online_pis and not (hotspot_id in last_online_pis):
    return True
  return False

def getStatus(hotspot_id):
  if hotspot_id in online_pis:
    return "ON"
  return "OFF"

def log(data):
  eastern = timezone('US/Eastern')
  time = eastern.localize(datetime.datetime.now())
  print "[%s] %s" % (time, data)

last_online_pis = getLastOnlinePis()
online_pis = getOnlinePis()

# TODO:
for hotspot_id in range(3,11):
  if statusChanged(hotspot_id):
    log("HOTSPOT %s went %s" % (hotspot_id, getStatus(hotspot_id)))

writeLastOnlinePis()
