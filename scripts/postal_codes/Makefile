
scrape:
	rm -f todos_cp.zip
	. `pwd`/.env/bin/activate; python3 download.py
	unzip todos_cp.zip
	rm -f todos_cp.zip
	rm -f leiame.txt
	rm -f distritos.txt
	iconv -f ISO-8859-15 -t utf-8 todos_cp.txt | sed 's/^M//' > all_postal_codes.csv
	rm -f todos_cp.txt
	iconv -f ISO-8859-15 -t utf-8 concelhos.txt | sed 's/^M//' > municipalities.csv
	rm -f concelhos.txt
	mv -f all_postal_codes.csv ../../storage/app/temp/postal_codes.csv
	mv -f municipalities.csv ../../storage/app/temp/municipalities.csv
	bash mysql_import.sh

install:
	virtualenv .env --python=/usr/bin/python3 --prompt=\(codpostais\)
	. `pwd`/.env/bin/activate; pip install -r requirements.txt
	@echo "--------------------------------------------------------------------------------------------"
	@echo ""
	@echo "Tudo instalado!"
	@echo "Agora, copia o credenciais.sample.ini para credenciais.ini:"
	@echo "    cp credenciais.sample.ini credenciais.ini"
	@echo ""
	@echo "E edita o ficheiro com o teu username e password no CTT.pt."
	@echo "Depois, é só correr:"
	@echo "    make scrape"
	@echo ""

deploy:
	git add ../data/codigos_postais.csv
	git commit -m "Atualização automática"
	git push origin master

installwin:
	virtualenv .env --python=C:\Python39\python.exe --prompt=\(codpostais\)
	. `pwd`/.env/bin/activate; pip install -r requirements.txt
	@echo "--------------------------------------------------------------------------------------------"
	@echo ""
	@echo "Tudo instalado!"
	@echo "Agora, copia o credenciais.sample.ini para credenciais.ini:"
	@echo "    cp credenciais.sample.ini credenciais.ini"
	@echo ""
	@echo "E edita o ficheiro com o teu username e password no CTT.pt."
	@echo "Depois, é só correr:"
	@echo "    make scrape"
	@echo ""
