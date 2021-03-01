USE ewater;
START TRANSACTION;

-- CREATE TEMPORARY TABLE FOR MUNICIPALITIES
CREATE TEMPORARY TABLE municipalities_temp (
	district_code INT,
    municipality_code INT,
    municipality_name VARCHAR(255)
);

-- LOAD DATA FROM THE CSV FILE INTO THE TEMPORARY TABLE
LOAD DATA LOCAL INFILE "/home/bmartins/dev/eWater/storage/app/temp/municipalities.csv"
INTO TABLE municipalities_temp
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n';

-- INSERT DATA INTO THE PERSISTENT TABLE
INSERT INTO municipalities(id, code, name)
	SELECT CAST(CONCAT(district_code, 0, municipality_code) AS UNSIGNED INT), municipality_code, municipality_name FROM municipalities_temp
	WHERE CAST(CONCAT(district_code, 0, municipality_code) AS UNSIGNED INT) NOT IN (SELECT id FROM municipalities);

-- DISPOSE OF THE TEMPORARY TABLE
TRUNCATE municipalities_temp;
DROP TABLE municipalities_temp;

-- CREATE TEMPORARY TABLE FOR ADDRESSES
CREATE TEMPORARY TABLE addresses_temp (
    district_code int,
    municipality_code int,
    locality_code int,
    locality_name varchar(255),
    artery_code int,
    artery_type varchar(255),
    primary_preposition varchar(255),
    artery_title varchar(255),
    secondary_preposition varchar(255),
    artery_designation varchar(255),
    artery_location varchar(255),
    section varchar(255),
    door_number varchar(255),
    client_name varchar(255),
    postal_code_number int,
    postal_code_extension int,
    postal_designation varchar(255)
);

-- LOAD DATA FROM THE CSV FILE INTO THE TEMPORARY TABLE
LOAD DATA LOCAL INFILE "/home/bmartins/dev/eWater/storage/app/temp/postal_codes.csv"
INTO TABLE addresses_temp
FIELDS TERMINATED BY ';'
LINES TERMINATED BY '\n';

-- EXTRACT LOCALITIES FROM TEMPORARY TABLE AND INSERT THEM INTO THE LOCALITIES TABLE
INSERT INTO localities(code, name, created_at, id, municipality_id, postal_code_number, postal_code_extension) 
	SELECT
		locality_code,
		locality_name,
		now(),
		CAST(CONCAT(municipality_code, 0, locality_code) AS UNSIGNED INT),
		CAST(CONCAT(district_code, 0, municipality_code) AS UNSIGNED INT),
		postal_code_number,
		postal_code_extension
	FROM addresses_temp
	WHERE CAST(CONCAT(municipality_code, 0, locality_code) AS UNSIGNED INT) NOT IN(SELECT id from localities)
	GROUP BY CAST(CONCAT(municipality_code, 0, locality_code) AS UNSIGNED INT);

-- EXTRACT STREETS FROM TEMPORARY TABLE AND INSERT THEM INTO THE STREETS TABLE, ONLY IF THE STREETS TABLE IS EMPTY
DROP FUNCTION IF EXISTS insertstreets;

DELIMITER $$

CREATE FUNCTION insertstreets()
	RETURNS INTEGER
	BEGIN
		SET @streetCount = (SELECT COUNT(*) FROM streets);
		IF @streetCount = 0
		THEN
			-- EXTRACT STREETS FROM TEMPORARY TABLE AND INSERT THEM INTO THE STREETS TABLE
			INSERT INTO streets(
				artery_code, 
				artery_type, 
				primary_preposition, 
				artery_title, 
				secondary_preposition, 
				artery_designation,
				section, 
				door_number, 
				client_name, 
				postal_code, 
				postal_code_extension, 
				postal_designation,
				created_at,
				artery_location,
				locality_id,
				full_street_designation
			)
			SELECT 
				artery_code, 
				artery_type, 
				primary_preposition, 
				artery_title, 
				secondary_preposition, 
				artery_designation, 
				section, 
				door_number, 
				client_name, 
				postal_code_number, 
				postal_code_extension, 
				postal_designation, 
				now(), 
				artery_location, 
				CAST(CONCAT(municipality_code, 0, locality_code) AS UNSIGNED INT),
				CONCAT(
					artery_type, 
					CONCAT(' ', primary_preposition), 
					CONCAT(' ', artery_title), 
					CONCAT(' ', secondary_preposition), 
					CONCAT(' ', artery_designation), 
					CONCAT(' ', door_number), 
					CONCAT(' ', artery_location), 
					CONCAT(
						' ', 
						postal_code_number, 
						'-', 
						LPAD(postal_code_extension, 3, 0), 
						' ', 
						postal_designation
					)
				)
			FROM addresses_temp;
		END IF;
		
		RETURN 1;
END$$

DELIMITER ;

SELECT insertstreets();

DROP FUNCTION IF EXISTS insertstreets;

-- DISPOSE OF THE TEMPORARY TABLE
TRUNCATE addresses_temp;
DROP TABLE addresses_temp;
COMMIT;