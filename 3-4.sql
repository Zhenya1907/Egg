-- 3. Напиши SQL-запрос
select
    u.name,
    u.phone,
    sum(o.subtotal)  as summ,
    avg(o.subtotal) as avgg,
    max(o.created) as lastdate
from users u
         join orders o on u.id = o.user_id
group by u.id;

-- 4. Напиши SQL-запросы
select
    max(w.salary)
from workers w
group by w.department_id;

select
    w.name
from workers w
where w.department_id = 3
  and w.salary > 90000;

-- Индекс для ускорения группировки по department_id
CREATE INDEX idx_department_id ON workers (department_id);

-- Индекс для ускорения выполнения условия WHERE
CREATE INDEX idx_department_salary ON workers (department_id, salary);
