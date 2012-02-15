LOCALROOT=/Applications/MAMP/htdocs/flowrss/

local:
	rsync -aP robot $(LOCALROOT)
	rsync -aP lib $(LOCALROOT)
	rsync -aP app $(LOCALROOT)
