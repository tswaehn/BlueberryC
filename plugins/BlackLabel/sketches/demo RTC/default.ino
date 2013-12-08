I have been working on some little tryout where a backup file was created before modifying the main textfile. Then when an error is thrown, the main file will be deleted (unlinked) and the backup file is returned instead.

Though, I have been breaking my head for about an hour on why I couldn't get my persmissions right to unlink the main file.

Finally I knew what was wrong: because I was working on the file and hadn't yet closed the file, it was still in use and ofcourse couldn't be deleted :)
