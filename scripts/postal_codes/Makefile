
scrape:
	rm -f todos_cp.zip
	. `pwd`/.env/bin/activate; python3 download.py
	unzip todos_cp.zip
	rm -f todos_cp.zip
	rm -f leiame.txt
	# converter para utf-8 e remover line endings de DOS
	iconv -f ISO-8859-15 -t utf-8 todos_cp.txt | sed 's/^M//' > todos_cp.csv
	rm -f todos_cp.txt
	iconv -f ISO-8859-15 -t utf-8 concelhos.txt | sed 's/^M//' > concelhos.csv
	rm -f concelhos.txt
	iconv -f ISO-8859-15 -t utf-8 distritos.txt | sed 's/^M//' > distritos.csv
	rm -f distritos.txt
	# adicionar cabeçalho e normalizar com o csvclean
	. `pwd`/.env/bin/activate; cat cp_header.txt todos_cp.csv | csvclean -d ";"; deactivate
	rm -f todos_cp.csv
	mv -f stdin_out.csv ../data/codigos_postais.csv
	. `pwd`/.env/bin/activate; cat conc_header.txt concelhos.csv | csvclean -d ";"; deactivate
	rm -f concelhos.csv
	mv -f stdin_out.csv ../data/concelhos.csv
	. `pwd`/.env/bin/activate; cat dist_header.txt distritos.csv | csvclean -d ";"; deactivate
	rm -f distritos.csv
	mv -f stdin_out.csv ../data/distritos.csv

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
