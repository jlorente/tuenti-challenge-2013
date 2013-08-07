#!/bin/bash
#/**
#* Tuenti challenge 2013
#* 
#* Challenge 12 
#* Whispering paRTTY
#* 
#* To execute this script you must have permission to write
#* on the /tmp/ directory and have installed the following 
#* programs:
#*     lame
#*     minimodem
#*
#* @author Jose Lorente Martin
#*/

base64 -d > /tmp/partty.mp3
lame --decode --quiet /tmp/partty.mp3 /tmp/partty.wav
rm /tmp/partty.mp3

minimodem --rx -q -f /tmp/partty.wav rtty
rm /tmp/partty.wav
