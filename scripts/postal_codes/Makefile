
scrape:
<<<<<<< HEAD
	rm -f todos_cp.zip
	. `pwd`/.env/bin/activate; python download.py
=======
	rm -Force todos_cp.zip
	. `pwd`/.env/bin/activate; python3 download.py
>>>>>>> e00037a8b659b063636d4fcda3fc4df73227a0f0
	unzip todos_cp.zip
	rm -Force todos_cp.zip
	rm -Force leiame.txt
	rm -Force distritos.txt
	iconv -Force ISO-8859-15 -t utf-8 todos_cp.txt | sed 's/^M//' > all_postal_codes.csv
	rm -Force todos_cp.txt
	iconv -Force ISO-8859-15 -t utf-8 concelhos.txt | sed 's/^M//' > municipalities.csv
	rm -Force concelhos.txt
	mv -Force all_postal_codes.csv ../../storage/app/temp/postal_codes.csv
	mv -Force municipalities.csv ../../storage/app/temp/municipalities.csv
	bash mysql_import.sh

install:
#	[[ ":$PATH:" != *":$(pwd):"* ]] && export PATH="$(pwd):${PATH}"
	virtualenv .env --python=/usr/bin/python3 --prompt=\(codpostais\)
	. `pwd`/.env/bin/activate; pip install --user -r requirements.txt
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
#	$$addPath = $$pwd.Path
#	$$regexAddPath = [regex]::Escape($$addPath)
#	$$arrPath = $$env:Path -split ';' | Where-Object {$$_ -notMatch "^$$regexAddPath\\?"}
#	$$env:Path = ($$arrPath + $$addPath) -join ';'
#	refreshenv
	virtualenv .env --python=C:\Python39\python.exe --prompt=\(codpostais\)
	. `pwd`/.env/bin/activate; pip install --user -r requirements.txt
	@echo "Necessário ter o chocolatey instalado"
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
