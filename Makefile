LOCALROOT=/Applications/MAMP/htdocs/flowrss/
INSTALLSERVER=flow-rss.com
INSTALLUSER=$(INSTALLSERVER)

local:
	rsync -aP robot $(LOCALROOT)
	rsync -aP lib $(LOCALROOT)
	rsync -aP app $(LOCALROOT)

install:
	rsync -aP robot $(INSTALLUSER)@$(INSTALLSERVER):
	rsync -aP lib $(INSTALLUSER)@$(INSTALLSERVER):
	rsync -aP app/ $(INSTALLUSER)@$(INSTALLSERVER):htdocs
