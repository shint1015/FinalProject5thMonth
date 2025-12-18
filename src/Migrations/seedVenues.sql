-- Seed data for `venues`
-- Assumes table created by `createTableVenue.sql`

-- Optional: clear existing data
-- TRUNCATE TABLE venues;

INSERT INTO venues (
  `name`,
  capacity,
  seat_id_format,
  notes,
  layout,
  `address`
) VALUES
(
  'Tokyo Grand Hall',
  1200,
  'TGH-{ROW}-{SEAT}',
  'Main flagship hall. Suitable for concerts and conferences.',
  JSON_OBJECT(
    'type', 'theater',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Orchestra','rows',30,'seatsPerRow',20),
      JSON_OBJECT('name','Balcony','rows',10,'seatsPerRow',20)
    )
  ),
  '1-1-1 Marunouchi, Chiyoda-ku, Tokyo'
),
(
  'Shinjuku Studio A',
  180,
  'SSA-{ROW}-{SEAT}',
  'Black box studio. Flexible seating.',
  JSON_OBJECT(
    'type', 'flex',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Floor','rows',12,'seatsPerRow',15)
    )
  ),
  '2-8-1 Nishishinjuku, Shinjuku-ku, Tokyo'
),
(
  'Osaka Riverside Arena',
  8500,
  'ORA-{BLOCK}-{ROW}-{SEAT}',
  'Large indoor arena. Sports and live events.',
  JSON_OBJECT(
    'type', 'arena',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Lower Bowl','blocks',8,'rowsPerBlock',25,'seatsPerRow',25),
      JSON_OBJECT('name','Upper Bowl','blocks',10,'rowsPerBlock',20,'seatsPerRow',25)
    )
  ),
  '3-2-1 Nakanoshima, Kita-ku, Osaka'
),
(
  'Yokohama Bay Conference Center',
  650,
  'YBC-{ROW}-{SEAT}',
  'Conference-focused venue with breakout rooms.',
  JSON_OBJECT(
    'type', 'conference',
    'rooms', JSON_ARRAY(
      JSON_OBJECT('name','Main','capacity',450),
      JSON_OBJECT('name','Breakout-1','capacity',100),
      JSON_OBJECT('name','Breakout-2','capacity',100)
    )
  ),
  '5-10-1 Minatomirai, Nishi-ku, Yokohama, Kanagawa'
),
(
  'Sapporo Civic Theater',
  980,
  'SCT-{ROW}-{SEAT}',
  'Classic proscenium theater.',
  JSON_OBJECT(
    'type', 'theater',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Stalls','rows',28,'seatsPerRow',22),
      JSON_OBJECT('name','Circle','rows',8,'seatsPerRow',20)
    )
  ),
  '1-1 Odori Nishi, Chuo-ku, Sapporo, Hokkaido'
),
(
  'Nagoya Live House',
  350,
  NULL,
  'Standing area. Ear protection recommended.',
  JSON_OBJECT(
    'type', 'standing',
    'zones', JSON_ARRAY(
      JSON_OBJECT('name','Front','capacity',120),
      JSON_OBJECT('name','Middle','capacity',150),
      JSON_OBJECT('name','Rear','capacity',80)
    )
  ),
  '4-5-6 Sakae, Naka-ku, Nagoya, Aichi'
),
(
  'Fukuoka Small Hall',
  420,
  'FSH-{ROW}-{SEAT}',
  NULL,
  JSON_OBJECT(
    'type', 'theater',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Main','rows',20,'seatsPerRow',21)
    )
  ),
  '7-8-9 Tenjin, Chuo-ku, Fukuoka, Fukuoka'
),
(
  'Kyoto Traditional Hall',
  300,
  'KTH-{ROW}-{SEAT}',
  'No food/drink inside. Historic building.',
  JSON_OBJECT(
    'type', 'hall',
    'notes', 'historic',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Main','rows',15,'seatsPerRow',20)
    )
  ),
  '10-11 Gionmachi, Higashiyama-ku, Kyoto'
),
(
  'Kobe Port Event Space',
  1200,
  'KPE-{ROW}-{SEAT}',
  'Multi-purpose space near the port.',
  JSON_OBJECT(
    'type', 'multipurpose',
    'sections', JSON_ARRAY(
      JSON_OBJECT('name','Floor','rows',24,'seatsPerRow',30)
    )
  ),
  '1-2-3 Harborland, Chuo-ku, Kobe, Hyogo'
),
(
  'Sendai Music Loft',
  220,
  NULL,
  'Small venue for acoustic sets.',
  JSON_OBJECT(
    'type', 'loft',
    'seating', JSON_OBJECT('style','mixed','chairs',120,'standing',100)
  ),
  '2-3-4 Ichibancho, Aoba-ku, Sendai, Miyagi'
);
