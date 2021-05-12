echo Total files
find cache | wc -l
echo Last 10 day count
find cache -atime -10 | wc -l
echo Last 50 day count
find cache -atime -50 | wc -l
echo Last 100 day count
find cache -atime -100 | wc -l
echo Last 300 day count 
find cache -atime -300 | wc -l

echo files older than 300 days to be deleted
find cache -atime +300 | wc -l
